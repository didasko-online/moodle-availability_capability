![Didasko Online Logo](https://raw.githubusercontent.com/didasko-online/moodle-availability_capability/main/pix/didasko-online-logo.png)

# Moodle Availability Condition: Capability

Restrict access to course sections or activities based on a user's capabilities.

This plugin adds a new availability condition to Moodle, allowing course creators to control visibility based on whether a user has one or more specified capabilities.

---

## ❗ Supported Versions

| Moodle Version | Branch       | Status        |
|----------------|--------------|----------------|
| 4.1+           | `main`       | ✅ Supported   |

---

## 💡 Features

- Restrict access to activities, resources, or entire sections.
- Match users based on any assignable Moodle capability.
- Supports multiple capabilities with AND/OR logic.

---

## 📦 Installation

1. Place this plugin in:  
   `availability/condition/capability/`
2. From your Moodle root directory, run the upgrade:
   
```bash
php admin/cli/upgrade.php
```

3. Or visit the **Site Administration** page in the Moodle admin UI to complete installation.

---

## 🧩 Usage

1. Go to a course and turn editing on.
2. Edit a section or activity.
3. Under **Restrict Access**, add a new restriction and select **Capability**.
4. Enter the capability string (e.g. `moodle/course:manageactivities`) using the search bar.
    * All capabilities added per condition are required by the user (AND logic).
5. To remove a capability from the condition, click the *x* on the tag in the top box.

---

## ⚖ License

[GNU GPL v3 or later](https://www.gnu.org/licenses/gpl-3.0.en.html)  
Copyright © Didasko Online

---

## 🖼 Support

![Didasko Icon](https://raw.githubusercontent.com/didasko-online/moodle-availability_capability/main/pix/didasko-online-icon.png)

Part of the [Didasko Online](https://didasko-online.com) suite of Moodle enhancements — **Education Forward**.
