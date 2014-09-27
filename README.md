Arista Sensors Cacti template
=========================
## Requirements ##
1. Arista SW :)   
2. [cacti 0.8.8b](http://cacti.net/download_cacti.php "cacti 0.8.8b")+ (tested, working)   
3. PHP >= 5.4.0  
4. [Net-SNMP](http://www.net-snmp.org/)  
5. working ip connection to Arista switch.

## Instalation ##
1. Install snmp (apt-get install snmp).
2. Install snmp-mibs-downloader (apt-get install snmp-mibs-downloader).
3. Import Templates in cacti (<your cacti domain>/templates_import.php).
4. Move arista-sensors.php into <your cacti dir on your server>/scripts/
5. Add from "Associated Graph Templates" dropdown in device screen th new arista templates "Arista_Chassis_Fan_ogi", "Arista_Chassis_Power_ogi" and "Arista_Chassis_Temp_ogi". Click create graph , select some or all arista templates and click create graph.
