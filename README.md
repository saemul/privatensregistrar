# PrivateNS Registrar WHMCS Module v4.0

This Module has already include:
1. DNS Manager
2. Domain Document Manager
3. TLD Product Sync
4. DNSSEC Manager (Domain .ID Only)

How to Install
1. Upload zip into your WHMCS installation folder
2. Extract zip
3. Open WHMCS Admin Page -> Setup -> Product/Services -> Domain Registrars
4. Activate PrivateNS Registrar
5. Insert Email Login, Client ID, Secret ID and your Reseller/API URL that you can get from https://developer.irsfa.id, for API Documentation please read here: https://developer.irsfa.id/documentation/
6. Save Changes
7. Open WHMCS Admin Page -> Setup -> Addon Modules
8. Activate PrivateNS Registrar
9. Insert Email Login, Client ID, Secret ID and your Reseller/API URL that you can get from https://developer.irsfa.id, for API Documentation please read here: https://developer.irsfa.id/documentation/
10. Checklist Access Control for Full Administrator
11. Save Changes
12. Paste Code below anywhere into public_html/templates/{YourTemplate}/clientareadomaindetails.tpl
				<li>
                    <a href="index.php?m=privatens_registrar&page=dnssecmanager&domain={$domain}">
                        Domain DNSSEC Manager
                    </a>
                </li>
				<li>
                    <a href="index.php?m=privatens_registrar">
                        Upload Domain Requirements
                    </a>
                </li>
13. Save Changes	

# WHOIS WHMCS V.6 Configuration
1. Login To Your FTP 
2. Open /includes/whoisservers.php
3. Add line  
.web.id|whois.pandi.or.id|DOMAIN NOT FOUND
.ac.id|whois.pandi.or.id|DOMAIN NOT FOUND
.co.id|whois.pandi.or.id|DOMAIN NOT FOUND
.or.id|whois.pandi.or.id|DOMAIN NOT FOUND
.sch.id|whois.pandi.or.id|DOMAIN NOT FOUND
.biz.id|whois.pandi.or.id|DOMAIN NOT FOUND
.my.id|whois.pandi.or.id|DOMAIN NOT FOUND
.id|whois.pandi.or.id|DOMAIN NOT FOUND
.ponpes.id|whois.pandi.or.id|DOMAIN NOT FOUND

4. Save File

# WHOIS WHMCS V.7 Configuration
1. Login To Your FTP 
2. Open File "dist.whois.json" in folder /resources/domains/
3. Edit Line 

 "extensions": ".co.id,.desa.id,.web.id,.ac.id,.or.id,.sch.id,.my.id,.biz.id",
    
To

 "extensions": ".id,.ponpes.id,.co.id,.desa.id,.web.id,.ac.id,.or.id,.sch.id,.my.id,.biz.id",   


4. Save File