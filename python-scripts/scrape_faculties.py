import requests
from bs4 import BeautifulSoup
import re
from datetime import datetime
from urllib.parse import urljoin
from pprint import pprint

BASE_URL = 'https://www.vysokeskoly.com'

def extract_dates(text):
    # Najdi všechna data ve formátu 10. 1. 2026 nebo 10.1.2026
    return re.findall(r'\d{1,2}\. ?\d{1,2}\. ?\d{4}', text)

def parse_date(date_str):
    try:
        return datetime.strptime(date_str.replace(" ", ""), "%d.%m.%Y").date().isoformat()
    except ValueError:
        return None

def scrape_faculty(url):
    res = requests.get(url)
    res.encoding = 'utf-8'  # nebo windows-1250, pokud by byl problém
    soup = BeautifulSoup(res.text, 'html.parser')

    faculty = {
        "name": soup.find("h1").text.strip() if soup.find("h1") else None,
        "description": soup.find("div", class_="perexDetail").text.strip() if soup.find("div", class_="perexDetail") else None,
        "address": None,
        "website": None,
        "email": None,
        "phone": None,
        "application_link": None,
        "admission_notes": None,
        "open_day_date": None,
        "open_day_url": None,
        "exam_dates": [],
        "application_fee_online": None,
        "application_fee_paper": None,
        "application_deadline": None,
        "bc_programs": [],
        "mgr_programs": [],
        "dr_programs": [],
        "logo_url": None,
        "banner_url": None,
    }

    # Kontakt a další info z div.kontaktDetail
    kontakt_div = soup.find("div", class_="kontaktDetail")
    if kontakt_div:
        kontakt_items = kontakt_div.find_all("div", class_="boxik")
        for item in kontakt_items:
            label = item.find("div", class_="titulek")
            value = item.find("div", class_="obsah")
            if not label or not value:
                continue
            label_text = label.text.lower()
            value_text = value.text.strip()

            if "adresa" in label_text:
                faculty["address"] = value_text
            elif "www" in label_text or "web" in label_text:
                a = value.find("a")
                faculty["website"] = urljoin(BASE_URL, a['href']) if a else value_text
            elif "e-mail" in label_text:
                faculty["email"] = value_text
            elif "telefon" in label_text:
                faculty["phone"] = value_text
            elif "odkaz" in label_text or "přihláška" in label_text:
                a = value.find("a")
                if a:
                    faculty["application_link"] = urljoin(BASE_URL, a['href'])

    # Tabulka přijímacího řízení
    table = soup.find("table", class_="tablePrijimacky")
    if table:
        rows = table.find_all("tr")
        for row in rows:
            cols = row.find_all("td")
            if len(cols) < 2:
                continue
            label = cols[0].text.strip().lower()
            value = cols[1].get_text(separator=" ", strip=True)

            # Den otevřených dveří + URL
            if "den otevřených dveří" in label:
                dates = extract_dates(value)
                if dates:
                    faculty["open_day_date"] = parse_date(dates[0])
                link = cols[1].find("a")
                if link:
                    faculty["open_day_url"] = urljoin(BASE_URL, link['href'])

            # Přijímací zkoušky (termíny)
            elif "přijímací zkoušky" in label and "cena" not in label:
                faculty["exam_dates"].extend(extract_dates(value))

            # Přihlášky ke studiu - termín
            elif "přihlášky ke studiu" in label:
                dates = extract_dates(value)
                if dates:
                    faculty["application_deadline"] = parse_date(dates[0])

            # Poplatek elektronická přihláška
            elif "elektronická" in label:
                match = re.search(r'(\d+)', value)
                if match:
                    faculty["application_fee_online"] = int(match.group(1))

            # Poplatek papírová přihláška
            elif "papírová" in label:
                match = re.search(r'(\d+)', value)
                if match:
                    faculty["application_fee_paper"] = int(match.group(1))

            # Poznámky o přijímacím řízení
            if "přesná data" in value.lower() or "poznámka" in label:
                faculty["admission_notes"] = value

    # Parsování datumů přijímacích zkoušek do ISO formátu
    faculty["exam_dates"] = [parse_date(d) for d in faculty["exam_dates"] if parse_date(d) is not None]

    # Logo fakulty
    logo_img = soup.find("div", class_="logoDetail")
    if logo_img:
        img = logo_img.find("img")
        if img and img.get("src"):
            faculty["logo_url"] = urljoin(BASE_URL, img["src"])

    # Banner fakulty
    banner_div = soup.find("div", class_="detail-banner")
    if banner_div:
        img = banner_div.find("img")
        if img and img.get("src"):
            faculty["banner_url"] = urljoin(BASE_URL, img["src"])

    # Programy (Bc., Mgr., Dr.)
    program_sections = soup.find_all("div", class_="program")
    for section in program_sections:
        title = section.find("h3")
        if not title:
            continue
        title_text = title.text.lower()
        programs = [li.text.strip() for li in section.find_all("li")]
        if "bakalářské" in title_text or "bc." in title_text:
            faculty["bc_programs"].extend(programs)
        elif "magisterské" in title_text or "mgr." in title_text:
            faculty["mgr_programs"].extend(programs)
        elif "doktorské" in title_text or "dr." in title_text:
            faculty["dr_programs"].extend(programs)

    return faculty

if __name__ == "__main__":
    # Zde změň URL na libovolnou fakultu
    url = 'https://www.vysokeskoly.com/vysoke-skoly-1/fakulta-informatiky'
    faculty_data = scrape_faculty(url)
    pprint(faculty_data)


