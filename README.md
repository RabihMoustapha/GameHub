# GameHub – Social Gaming Platform

GameHub is a complete web application that combines a social network with an arcade-style game hub. Built with PHP and MySQL, it allows users to register, create profiles, post updates, comment, add friends, send private messages, and play embedded HTML5 games with leaderboards.

## Features

### 👤 User System
- Secure registration and login with password hashing
- User profiles with avatar upload and bio
- Session management

### 📱 Social Feed
- Create text/image posts
- Comment on posts (AJAX-powered)
- View posts from yourself and friends

### 🤝 Friends & Messaging
- Send/accept friend requests
- List of friends with online status (polling-based)
- Real-time chat interface (polling every 3 seconds)

### 🎮 Game Library
- Browse available games
- Play embedded HTML5 games (two sample games included)
- Submit scores to global leaderboards
- View top scores per game

### 🏆 Leaderboards
- Track your best scores on your profile
- Global leaderboards for each game

## Technologies Used

- **Backend:** PHP (MySQLi with prepared statements)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3 (modern, responsive design), vanilla JavaScript
- **Server:** WAMP (Apache, MySQL, PHP)
- **Additional:** AJAX for comments and chat, file uploads for avatars/post images

## Installation Guide

### Prerequisites
- WAMP (Windows, Apache, MySQL, PHP) installed and running
- Git (optional, to clone the repository)

### Step-by-Step Setup

1. **Clone or download the project**
   ```bash
   git clone https://github.com/yourusername/gamehub.git
   ```
   Or download the ZIP and extract it into `C:\wamp64\www\gamehub`.

2. **Create the database**
   - Open phpMyAdmin at `http://localhost/phpmyadmin`
   - Import the `database.sql` file located in the project root.
   - This will create the `gamehub` database and all required tables.

3. **Configure database connection**
   - Open `includes/config.php`
   - Adjust the database credentials if needed (default: root / empty password).

4. **Set up upload directories**
   - Ensure the following folders exist and are writable:
     - `assets/uploads/avatars/`
     - `assets/uploads/posts/`
   - On Windows, right-click each folder → Properties → Security → give `Everyone` Write permission (or set appropriate permissions for the web server user).

5. **Run the application**
   - Start WAMP (Apache & MySQL)
   - Open your browser and navigate to `http://localhost/gamehub/`
   - Register a new account and start using the platform!

## Folder Structure

```
gamehub/
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── script.js
│   └── uploads/
│       ├── avatars/
│       └── posts/
├── games/
│   ├── clicker/
│   │   └── index.html
│   └── memory/
│       └── index.html
├── includes/
│   ├── config.php
│   ├── header.php
│   └── footer.php
├── index.php
├── register.php
├── login.php
├── logout.php
├── profile.php
├── edit_profile.php
├── games.php
├── game.php
├── comment.php
├── submit_score.php
├── friends.php
├── add_friend.php
├── accept_friend.php
├── search_users.php
├── messages.php
├── fetch_messages.php
├── send_message.php
├── database.sql
└── README.md
```

## Customization

- **Add more games:** Place your HTML5 game in a new folder under `games/`, then insert a record in the `games` table.
- **Styling:** Modify `assets/css/style.css` to change the look and feel.
- **Functionality:** Extend the PHP files to add new features.

## Security Notes

- All database queries use prepared statements to prevent SQL injection.
- Passwords are hashed with `password_hash()`.
- File uploads are validated by MIME type.
- Sessions are used for authentication.

## Troubleshooting

- **Blank page / errors:** Enable PHP error reporting in `config.php` temporarily.
- **Database connection fails:** Check credentials and that MySQL is running.
- **Uploads not working:** Verify folder permissions.
- **Games not loading:** Ensure the game paths in the `games` table are correct (relative to project root).

## License

This project is open-source and available under the MIT License.

---

**Enjoy gaming and connecting with friends on GameHub!**
