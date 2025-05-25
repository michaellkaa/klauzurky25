import requests
from bs4 import BeautifulSoup
import re
import os
import mysql.connector
import time


def insert_into_database(faculty):
    try:
        if not faculty.get('name') or not faculty.get('address'):
            print(f"Přeskočeno (chybí jméno nebo město): {faculty.get('name')} / {faculty.get('location')}")
            return

        connection = mysql.connector.connect(
            host='89.168.43.83',
            user='michaelka',
            password='michaelka1',
            database='michaelka_klauzury_html',
            charset='utf8mb4',
            collation='utf8mb4_general_ci'
        )
        if connection.is_connected():
            cursor = connection.cursor()

            query = """
                INSERT INTO faculties
                (university, name, description, address, website, email, phone, application_link,
                admission_notes, open_day_dates, open_day_url, exam_dates, application_fee,
                application_deadlines, bc_programs, mgr_programs, dr_programs, logo_url,
                banner_url, facebook_url, instagram_url, twitter_url, fields_of_study)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            """
            values = (
                faculty.get('university', None),
                faculty.get('name', ''),
                faculty.get('description', ''),
                faculty.get('address', ''),
                faculty.get('website', ''),
                faculty.get('email', ''),
                faculty.get('phone', ''),
                faculty.get('application_link', ''),
                faculty.get('admission_notes', ''),
                faculty.get('open_day_dates', []),
                faculty.get('open_day_url', ''),
                faculty.get('exam_dates', ''),
                faculty.get('application_fee', ''),
                faculty.get('application_deadlines', []),
                faculty.get('bc_programs', []),
                faculty.get('mgr_programs', []),
                faculty.get('dr_programs', []),
                faculty.get('logo_url', ''),
                faculty.get('banner_url', ''),
                faculty.get('facebook_url', ''),
                faculty.get('instagram_url', ''),
                faculty.get('twitter_url', ''),
                faculty.get('fields_of_study', []),
            )

            cursor.execute(query, values)
            connection.commit()
            
    except Exception as e:
        print(f"Chyba při ukládání: {e}")
    finally:
        if 'connection' in locals() and connection.is_connected():
            cursor.close()
            connection.close()




BASE_URL = "https://www.vysokeskoly.com"
URL = BASE_URL + "/PS/Vysoke-skoly"

IMG_DIR = "fakulty_obrazky"
os.makedirs(IMG_DIR, exist_ok=True)

response = requests.get(URL)
response.encoding = 'windows-1250'
soup = BeautifulSoup(response.text, 'html.parser')
bloky_skol = soup.select(".ListSkola")

for blok in bloky_skol:
    odkaz_element = blok.select_one("a.fullOdkaz")
    if not odkaz_element:
        continue

    univerzita_nazev = blok.select_one(".nazev h3")
    if not univerzita_nazev:
        continue
    univerzita_nazev = univerzita_nazev.get_text(strip=True)

    fakulty_a = blok.select("ul.seznamFakult li a")
    if not fakulty_a:
        continue

    fakulty_odkazy = [a['href'] for a in fakulty_a]

    for odkaz_fakulty in fakulty_odkazy:
        url_fakulty = odkaz_fakulty if odkaz_fakulty.startswith('http') else BASE_URL + odkaz_fakulty
        fakulta = requests.get(url_fakulty)
        fakulta.encoding = 'windows-1250'
        fakulta_soup = BeautifulSoup(fakulta.text, 'html.parser')

        nazev = fakulta_soup.select_one(".web_2")
        nazev = nazev.get_text(strip=True) if nazev else "Název nenalezen"

        adresa = fakulta_soup.select_one(".big-adresa")
        lokace = adresa.get_text(strip=True) if adresa else "Adresa nenalezena"

        popis_element = fakulta_soup.select_one(".BoxAnot")
        if popis_element:
            odstavce = popis_element.find_all("p")
            popis_full = "\n\n".join(p.get_text(" ", strip=True) for p in odstavce) if odstavce else popis_element.get_text(" ", strip=True)
        else:
            popis_full = "Popis nenalezen"

        popis = popis_full
        typ_studia = ""

        if "Typ studia:" in popis_full:
            popis = popis_full.split("Typ studia:")[0].strip()
            zbytek = popis_full.split("Typ studia:")[1]
            typ_studia = zbytek.split("Vedení")[0].strip(" :,\n") if "Vedení" in zbytek else zbytek.strip(" :,\n")
        elif "Vedení" in popis_full:
            popis = popis_full.split("Vedení")[0].strip()

        email = telefon = web = ""
        kontakty_bloky = fakulta_soup.select(".rozdel-box, .kontakty2, .big-adresa")

        for blok in kontakty_bloky:
            text = blok.get_text(separator="\n", strip=True)

            if not email:
                email_match = re.search(r"[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}", text)
                if email_match:
                    email = email_match.group(0)

            if not telefon:
                telefon_match = re.search(r"(\+?\d{3} ?\d{3} ?\d{3})", text)
                if telefon_match:
                    telefon = telefon_match.group(0)

            if not web:
                odkazy = blok.find_all("a", href=True)
                for a in odkazy:
                    href = a['href']
                    if any(soc in href for soc in ["facebook", "instagram", "twitter", "youtube", "linkedin"]):
                        continue
                    if "web" in a.find_previous(string=True).lower():
                        web = href
                        break

        facebook = instagram = twitter = youtube = linkedin = ""
        for link in fakulta_soup.select("ul.FKsocial a"):
            href = link.get("href", "")
            classes = link.get("class", [])
            if "ico-facebook" in classes:
                facebook = href
            elif "ico-instagram" in classes:
                instagram = href
            elif "ico-twitter" in classes:
                twitter = href
            elif "ico-youtube" in classes:
                youtube = href
            elif "ico-linkedin" in classes:
                linkedin = href

        banner_url = logo_url = ""
        banner = fakulta_soup.select_one(".FullBigBanner .big-skolaImg img")
        if banner:
            banner_url = BASE_URL + banner.get("src", "")
            try:
                img_data = requests.get(banner_url).content
                with open(f"{IMG_DIR}/{nazev}_banner.jpg", "wb") as f:
                    f.write(img_data)
            except:
                pass

        logo = fakulta_soup.select_one(".FullBigBanner .big-skolaLogo img")
        if logo:
            logo_url = BASE_URL + logo.get("src", "")
            try:
                img_data = requests.get(logo_url).content
                with open(f"{IMG_DIR}/{nazev}_logo.jpg", "wb") as f:
                    f.write(img_data)
            except:
                pass

        den_odveri_datum = ""
        den_odveri_odkaz = ""
        prijimacky_cena_prihlasky = ""
        prihlaska_odkaz = ""
        prijimacky_datum = "" 
        prihlasky_datum = "" 

        prijimacky_tab = fakulta_soup.select_one("table.tablePrijimacky")
 
        if prijimacky_tab:
            for row in prijimacky_tab.select("tr"):
                cols = row.select("td")
                if len(cols) == 2:
                    nazev_raw = cols[0].get_text(" ", strip=True).replace(":", "").lower()
                    obsah = cols[1]

                    if "den otevřených dveří" in nazev_raw:
                        text = obsah.get_text(" ", strip=True)
                        odkazy = re.findall(r"https?://\S+", text)
                        if odkazy:
                            den_odveri_odkaz = ", ".join(odkazy)
                        text_bez_odkazu = re.sub(r"https?://\S+", "", text).strip(" ,")
                        den_odveri_datum = text_bez_odkazu

                    elif "přihlášky ke studiu" in nazev_raw or "termín přihlášek" in nazev_raw:
                        prihlasky_datum = obsah.get_text(" ", strip=True)

                    elif "přijímací zkoušky" in nazev_raw and "(cena" not in nazev_raw:
                        prijimacky_datum = obsah.get_text(" ", strip=True)

                    elif "přihláška" in nazev_raw and "cena" in nazev_raw:
                        prijimacky_cena_prihlasky = obsah.get_text(" ", strip=True)

                for a in row.select("a[href]"):
                    if "prihlaska" in a["href"]:
                        prihlaska_odkaz = a["href"]

        bc_programs = []
        mgr_programs = []
        phd_programs = []

        radky_programu = fakulta_soup.select("table tr[class*='typ_']")
        for tr in radky_programu:
            tds = tr.find_all("td")
            if len(tds) >= 3:
                nazev_programu = tds[1].get_text(strip=True)
                typ_studia_programu = tds[2].get_text(strip=True)

                if "Bakalářské" in typ_studia_programu:
                    bc_programs.append(nazev_programu)
                elif "Magisterské" in typ_studia_programu:
                    mgr_programs.append(nazev_programu)
                elif "Doktorské" in typ_studia_programu:
                    phd_programs.append(nazev_programu)

        bc_programs = ", ".join(sorted(set(bc_programs)))
        mgr_programs = ", ".join(sorted(set(mgr_programs)))
        phd_programs = ", ".join(sorted(set(phd_programs)))

        zamereni_list = fakulta_soup.select("#oboryListViz li")
        zamereni = [li.get_text(strip=True) for li in zamereni_list] if zamereni_list else []
        zamereni = ", ".join(zamereni)

        faculty_data = {
            'university': univerzita_nazev,
            'name': nazev,
            'description': popis,
            'address': lokace,
            'website': web,
            'email': email,
            'phone': telefon,
            'application_link': prihlaska_odkaz,
            'admission_notes': typ_studia,
            'open_day_dates': den_odveri_datum,
            'open_day_url': den_odveri_odkaz,
            'exam_dates': prijimacky_datum,
            'application_fee': prijimacky_cena_prihlasky,
            'application_deadlines': prihlasky_datum,
            'bc_programs': bc_programs,
            'mgr_programs': mgr_programs,
            'dr_programs': phd_programs,
            'logo_url': logo_url,
            'banner_url': banner_url,
            'facebook_url': facebook,
            'instagram_url': instagram,
            'twitter_url': twitter,
            'fields_of_study': zamereni
        }
        
        insert_into_database(faculty_data)
        time.sleep(1) #protoze jsem cekala ze fakult bude vic nez 144

