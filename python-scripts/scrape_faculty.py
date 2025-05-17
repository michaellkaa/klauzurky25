import requests
from bs4 import BeautifulSoup
import re
import os

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
        prijimacky_datum = ""
        prijimacky_cena_prihlasky = ""
        prihlaska_odkaz = ""

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
                        prijimacky_datum = obsah.get_text(" ", strip=True)

                    elif "přihláška" in nazev_raw and "cena" in nazev_raw:
                        prijimacky_cena_prihlasky = obsah.get_text(" ", strip=True)

                for a in row.select("a[href]"):
                    if "prihlaska" in a["href"]:
                        prihlaska_odkaz = a["href"]


        print("=======================================")
        print(f"Název fakulty: {nazev}")
        print(f"Lokace: {lokace}")
        print(f"Popis: {popis}")
        print(f"Typ studia: {typ_studia}")
        print(f"Web: {web}")
        print(f"E-mail: {email}")
        print(f"Telefon: {telefon}")
        print(f"Facebook: {facebook}")
        print(f"Instagram: {instagram}")
        print(f"Twitter: {twitter}")
        print(f"YouTube: {youtube}")
        print(f"LinkedIn: {linkedin}")
        print(f"Logo URL: {logo_url}")
        print(f"Banner URL: {banner_url}")
        print("---- Přijímací řízení ----")
        print(f"Den otevřených dveří: {den_odveri_datum}")
        print(f"Odkaz na DOD: {den_odveri_odkaz}")
        print(f"Termín přihlášek: {prijimacky_datum}")
        print(f"Cena přihlášky: {prijimacky_cena_prihlasky}")
        print(f"Odkaz na přihlášku: {prihlaska_odkaz}")
        print("=======================================")
