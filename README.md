# Xophz Thor's Hammer

> **Category:** Castle Walls · **Version:** 1.0.0

Bring the hammer down and ban spammers from making actions on your site.

## Description

**Thor's Hammer** is a targeted blocking and banning utility for the COMPASS platform. It empowers site administrators to decisively restrict malicious users, disruptive bots, and spammers from interacting with the site based on IP address, email address, or WordPress User ID.

### Core Capabilities

- **Ban Engine** – Restrict access and actions using three ban types:
  - `ip`: Block specific IP addresses.
  - `email`: Block specific email addresses (useful for form submissions/registrations).
  - `user_id`: Revoke access for registered WordPress users.
- **REST API** – Fully featured API to create, view, and lift bans.
- **Expiry Rules** – Bans can be permanent or set to expire at a specific `datetime`.

## Requirements

- **Xophz COMPASS** parent plugin (active)
- WordPress 5.8+, PHP 7.4+

## Installation

1. Ensure **Xophz COMPASS** is installed and active.
2. Upload `xophz-compass-thors-hammer` to `/wp-content/plugins/`.
3. Activate through the Plugins menu.
4. On activation, the plugin initializes the `wp_xophz_thors_hammer_bans` database table.
5. Access via the My Compass dashboard → **Thor's Hammer**.

## Database Tables

| Table | Purpose |
|---|---|
| `wp_xophz_thors_hammer_bans` | Stores ban records, including type, value, reason, and expiry date. |

## Frontend Routes

| Route | View | Description |
|---|---|---|
| `/thors-hammer` | Dashboard | Ban management interface and active restrictions list |

## Changelog

### 1.0.0

- Initial release featuring database-driven banning by IP, Email, and User ID.
