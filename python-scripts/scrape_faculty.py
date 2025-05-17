import requests
from bs4 import BeautifulSoup
import re

BASE_URL = "https://www.vysokeskoly.com"
url = BASE_URL + "/PS/Vysoke-skoly"

response = requests.get(url)
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

        nazev_element = fakulta_soup.select_one(".web_2")
        nazev = nazev_element.get_text(strip=True) if nazev_element else "Název nenalezen"

        adresa_element = fakulta_soup.select_one(".big-adresa")
        lokace = adresa_element.get_text(strip=True) if adresa_element else "Adresa nenalezena"

        popis_element = fakulta_soup.select_one(".BoxAnot")
        if popis_element:
            odstavce = popis_element.find_all("p")
            if odstavce:
                popis_full = "\n\n".join(p.get_text(" ", strip=True) for p in odstavce)
            else:
                # fallback, když tam nejsou <p>, ale jen text
                popis_full = popis_element.get_text(" ", strip=True)
        else:
            popis_full = "Popis nenalezen"

        popis = popis_full

        typ_studia = ""

        if "Typ studia:" in popis_full:
            casti = popis_full.split("Typ studia:")
            popis = casti[0].strip()
            zbytek = casti[1]
            if "Vedení" in zbytek:
                typ_studia_cast, _ = zbytek.split("Vedení", 1)
                typ_studia = typ_studia_cast.strip(" :,\n")
            else:
                typ_studia = zbytek.strip(" :,\n")
        elif "Vedení" in popis_full:
            popis = popis_full.split("Vedení", 1)[0].strip()

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
                for blok in kontakty_bloky:
                    odkazy = blok.find_all("a", href=True)
                    for a in odkazy:
                        if "Web:" in blok.get_text():
                            if "facebook" in a["href"] or "instagram" in a["href"] or "twitter" in a["href"] or "youtube" in a["href"] or "linkedin" in a["href"]:
                                continue
                            pred_textem = a.find_previous(string=True)
                            if pred_textem and "web" in pred_textem.lower():
                                web = a["href"]
                                break
                    if web:
                        break

        facebook = instagram = twitter = youtube = linkedin = ""
        social_links = fakulta_soup.select("ul.FKsocial a")
        for link in social_links:
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
        banner_elem = fakulta_soup.select_one(".FullBigBanner .big-skolaImg img")
        if banner_elem:
            banner_url = BASE_URL + banner_elem.get("src", "")
        logo_elem = fakulta_soup.select_one(".FullBigBanner .big-skolaLogo img")
        if logo_elem:
            logo_url = BASE_URL + logo_elem.get("src", "")



        


        print(f"Název: {nazev}")
        #print(f"Odkaz: {url_fakulty}")
        print(f"Lokace: {lokace}")
        print(f"Popis: {popis}")
        print(f"Typ studia: {typ_studia}")
        print(f"Web: {web or 'Nenalezeno'}")
        print(f"E-mail: {email or 'Nenalezen'}")
        print(f"Telefon: {telefon or 'Nenalezen'}")
        print(f"Facebook: {facebook}")
        print(f"Instagram: {instagram}")
        print(f"Twitter: {twitter}")
        print(f"YouTube: {youtube}")
        print(f"LinkedIn: {linkedin}")
        print(f"Logo URL: {logo_url}")
        print(f"Banner URL: {banner_url}")
        print(f"Přijímací řízení:\n{prijimacky_text or 'Nenalezeno'}")
        print(f"Datum přijímaček: {datum_prijimacek or 'Nenalezeno'}")
        print("--------------")

