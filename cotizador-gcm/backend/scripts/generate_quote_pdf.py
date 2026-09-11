import json
import os
import sys
import tempfile
from datetime import datetime

from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER, TA_LEFT, TA_RIGHT
from reportlab.lib.pagesizes import letter
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import inch
from reportlab.platypus import (
    Image,
    SimpleDocTemplate,
    Paragraph,
    Spacer,
    Table,
    TableStyle,
)


MONTHS = [
    "enero",
    "febrero",
    "marzo",
    "abril",
    "mayo",
    "junio",
    "julio",
    "agosto",
    "septiembre",
    "octubre",
    "noviembre",
    "diciembre",
]


def money(value):
    try:
        return f"C${float(value):,.2f}"
    except Exception:
        return "C$0.00"


def number(value, decimals=2):
    try:
        return f"{float(value):,.{decimals}f}"
    except Exception:
        return f"{0:.{decimals}f}"


def today_es():
    now = datetime.now()
    return f"Managua, {now.day:02d} de {MONTHS[now.month - 1]} de {now.year}"


def p(text, style):
    return Paragraph(str(text or ""), style)


def parse_cost_breakdown(value):
    if isinstance(value, dict):
        return value

    if isinstance(value, str):
        try:
            parsed = json.loads(value)
            return parsed if isinstance(parsed, dict) else {}
        except Exception:
            return {}

    return {}


def build_pdf(payload):
    quote = payload["quote"]
    filename = payload["filename"]
    backend_root = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
    logo_path = os.path.join(backend_root, "templates", "sello-gcm.png")
    out_dir = os.path.join(tempfile.gettempdir(), "cotizador_gcm_exports")
    os.makedirs(out_dir, exist_ok=True)
    out_path = os.path.join(out_dir, filename)

    styles = getSampleStyleSheet()
    normal = ParagraphStyle(
        "NormalGCM",
        parent=styles["Normal"],
        fontName="Helvetica",
        fontSize=10.5,
        leading=14,
        textColor=colors.HexColor("#111827"),
    )
    small = ParagraphStyle(
        "SmallGCM",
        parent=normal,
        fontSize=9,
        leading=12,
        textColor=colors.HexColor("#374151"),
    )
    title = ParagraphStyle(
        "TitleGCM",
        parent=normal,
        fontName="Helvetica-Bold",
        fontSize=16,
        leading=20,
        alignment=TA_CENTER,
        textColor=colors.HexColor("#0B5E73"),
    )
    section = ParagraphStyle(
        "SectionGCM",
        parent=normal,
        fontName="Helvetica-Bold",
        fontSize=11,
        leading=14,
        textColor=colors.HexColor("#111827"),
    )
    right = ParagraphStyle("RightGCM", parent=normal, alignment=TA_RIGHT)
    center = ParagraphStyle("CenterGCM", parent=normal, alignment=TA_CENTER)
    title_right = ParagraphStyle("TitleRightGCM", parent=title, alignment=TA_RIGHT)
    bold_center = ParagraphStyle(
        "BoldCenterGCM",
        parent=center,
        fontName="Helvetica-Bold",
    )

    doc = SimpleDocTemplate(
        out_path,
        pagesize=letter,
        rightMargin=0.7 * inch,
        leftMargin=0.7 * inch,
        topMargin=0.55 * inch,
        bottomMargin=0.55 * inch,
    )

    customer = quote.get("customer_name") or "Cliente"
    ruc = quote.get("customer_ruc") or ""
    phone = quote.get("customer_phone") or ""
    quote_number = quote.get("quote_number") or ""
    volume = quote.get("volume_gallons") or 0
    cost_breakdown = parse_cost_breakdown(quote.get("cost_breakdown"))
    product = cost_breakdown.get("productName") or "Diesel"
    fuel_unit_price = cost_breakdown.get("fuelCustomerPricePerGallon") or 0
    fuel_total = cost_breakdown.get("fuelCustomerTotal") or float(fuel_unit_price) * float(volume or 0)
    distance = quote.get("distance_km") or 0

    story = []
    logo = Image(logo_path, width=1.55 * inch, height=1.12 * inch) if os.path.exists(logo_path) else ""
    header = Table(
        [
            [
                logo,
                [
                    p("GCM TRANSPORTES, S.A.", title_right),
                    p(f"Cotización No. {quote_number}", right),
                ],
            ]
        ],
        colWidths=[1.7 * inch, 5.3 * inch],
    )
    header.setStyle(
        TableStyle(
            [
                ("VALIGN", (0, 0), (-1, -1), "TOP"),
                ("ALIGN", (0, 0), (0, 0), "LEFT"),
                ("ALIGN", (1, 0), (1, 0), "RIGHT"),
                ("LEFTPADDING", (0, 0), (-1, -1), 0),
                ("RIGHTPADDING", (0, 0), (-1, -1), 0),
                ("TOPPADDING", (0, 0), (-1, -1), 0),
                ("BOTTOMPADDING", (0, 0), (-1, -1), 0),
            ]
        )
    )
    story.append(header)
    story.append(Spacer(1, 0.22 * inch))
    story.append(p(today_es(), right))
    story.append(Spacer(1, 0.18 * inch))

    attention = [
        [p("<b>Atención</b>", normal), p(customer, normal)],
        [p("<b>RUC</b>", normal), p(ruc or "-", normal)],
        [p("<b>Teléfono</b>", normal), p(phone or "-", normal)],
        [p("<b>Confidencial</b>", normal), p("Sus Manos", normal)],
    ]
    attention_table = Table(attention, colWidths=[1.2 * inch, 5.0 * inch])
    attention_table.setStyle(
        TableStyle(
            [
                ("VALIGN", (0, 0), (-1, -1), "TOP"),
                ("BOTTOMPADDING", (0, 0), (-1, -1), 4),
                ("TOPPADDING", (0, 0), (-1, -1), 2),
            ]
        )
    )
    story.append(attention_table)
    story.append(Spacer(1, 0.22 * inch))

    story.append(
        p(
            "Reciba un cordial saludo de nuestra parte. A continuación tenemos el agrado de presentarle nuestra oferta de servicio de transporte y suministro de producto.",
            normal,
        )
    )
    story.append(Spacer(1, 0.2 * inch))
    story.append(p("Oferta:", section))

    offer_data = [
        [
            p("Producto", bold_center),
            p("Origen", bold_center),
            p("Destino", bold_center),
            p("Precio combustible unitario C$/Gln", bold_center),
            p("Cantidad", bold_center),
            p("Total", bold_center),
        ],
        [
            p(product, center),
            p("GCM Transportes", center),
            p(customer, center),
            p(money(fuel_unit_price), center),
            p(number(volume, 0), center),
            p(money(fuel_total), center),
        ],
    ]
    offer_table = Table(
        offer_data,
        colWidths=[1.35 * inch, 1.05 * inch, 1.15 * inch, 1.45 * inch, 0.8 * inch, 1.2 * inch],
    )
    offer_table.setStyle(
        TableStyle(
            [
                ("BACKGROUND", (0, 0), (-1, 0), colors.HexColor("#BFEAF7")),
                ("TEXTCOLOR", (0, 0), (-1, 0), colors.HexColor("#111827")),
                ("FONTNAME", (0, 0), (-1, 0), "Helvetica-Bold"),
                ("GRID", (0, 0), (-1, -1), 0.7, colors.HexColor("#111827")),
                ("VALIGN", (0, 0), (-1, -1), "MIDDLE"),
                ("ALIGN", (0, 0), (-1, -1), "CENTER"),
                ("TOPPADDING", (0, 0), (-1, -1), 7),
                ("BOTTOMPADDING", (0, 0), (-1, -1), 7),
            ]
        )
    )
    story.append(offer_table)
    story.append(Spacer(1, 0.18 * inch))

    details = [
        ["Distancia recorrida", f"{number(distance, 2)} km"],
    ]
    details_table = Table(details, colWidths=[2.2 * inch, 2.0 * inch])
    details_table.setStyle(
        TableStyle(
            [
                ("GRID", (0, 0), (-1, -1), 0.4, colors.HexColor("#CBD5E1")),
                ("BACKGROUND", (0, 0), (0, -1), colors.HexColor("#F8FAFC")),
                ("FONTNAME", (0, 0), (0, -1), "Helvetica-Bold"),
                ("ALIGN", (1, 0), (1, -1), "RIGHT"),
                ("TOPPADDING", (0, 0), (-1, -1), 5),
                ("BOTTOMPADDING", (0, 0), (-1, -1), 5),
            ]
        )
    )
    story.append(details_table)
    story.append(Spacer(1, 0.22 * inch))

    story.append(p("Condiciones de Compra:", section))
    conditions = [
        "Orden de compra con 48 horas de anticipación.",
        "Entrega de producto según coordinación operativa.",
        "Trámite de cheque 15 días.",
        "Precio sujeto a volumen y ruta cotizada.",
        "Tenemos carta de no retención IR.",
        "Vigencia de precio según confirmación comercial.",
    ]
    for idx, condition in enumerate(conditions, 1):
        story.append(p(f"{idx}. {condition}", normal))

    story.append(Spacer(1, 0.28 * inch))
    story.append(p("Sin más a qué referirme, me despido a la espera de sus comentarios.", normal))
    story.append(Spacer(1, 0.32 * inch))
    story.append(p("Atentamente,", normal))
    story.append(Spacer(1, 0.25 * inch))
    story.append(p("Supervisor de ventas y operaciones", normal))
    story.append(p("GCM Transportes S.A.", normal))
    story.append(Spacer(1, 0.18 * inch))
    story.append(
        p(
            "Carretera Cuesta del Plomo Km 9 1/2 Base Cuesta del Plomo, Ciudad Sandino",
            small,
        )
    )
    story.append(p("Teléfono: (505) 22243064   Cel. (505) 83960715", small))
    story.append(p("E-mail: llopez@gcmtransportes.com", small))

    doc.build(story)
    return out_path


def main():
    payload = json.loads(sys.stdin.read())
    out_path = build_pdf(payload)
    print(json.dumps({"path": out_path}))


if __name__ == "__main__":
    main()
