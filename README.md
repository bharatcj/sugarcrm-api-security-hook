### SugarCRM API Security Hook

## 📌 Overview
This **SugarCRM logic hook** enhances security by **monitoring API access** and restricting unauthorized IPs.  
It logs **API requests**, prevents unauthorized **noLoginRequired GET calls**, and ensures compliance with security policies.

## 🚀 Features
✅ Restricts API access to **whitelisted IPs**  
✅ Logs **unauthorized access attempts** for auditing  
✅ Prevents unauthorized **noLoginRequired GET requests**  
✅ Uses **before_api_call** hook for real-time security  
✅ Custom logging system for tracking suspicious activity  

## 🔧 Installation
1. Copy **ApiCheckHook.php** to `custom/modules/`.
2. Add the hook reference in `custom/modules/logic_hooks.php`.
3. Run **Quick Repair & Rebuild** in SugarCRM.

## ⚙️ Configuration
To modify **allowed IPs**, update `ApiCheckHook.php`:
```php
$whitelistedIPs = [
    '127.0.0.1',  
    'XXX.XXX.XXX.XXX', 
    'YYY.YYY.YYY.YYY'
];
```

## 📌 Logging
Unauthorized API access attempts are **logged automatically** in:
```
/custom/logs/ApiAccessLogs/YYYY-MM-DD/
```
Each log file contains:
- Remote Address  
- Request URI  
- User Agent  
- Forwarded For IP  

## 📜 License
This project is licensed under the **MIT License**.

## 💡 Contributing
Pull requests are welcome! Please follow **best security practices** when modifying the logic.