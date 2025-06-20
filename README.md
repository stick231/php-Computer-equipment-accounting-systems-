# Computer Equipment Accounting Systems

Welcome to **Computer Equipment Accounting Systems**!

This project helps you manage and account for computer equipment in an organized and scalable way. It leverages Docker for easy setup and includes a migration script to configure your environment.

---

## 📸 Project Screenshot

Below are some screenshots of the application, located in the `images/` directory.

| Main Screen                   | Main Table                    | Custom Table                  |
|-------------------------------|-------------------------------|-------------------------------|
| ![Main Screenshot](images/main%20screenshot.png) | ![Main Table Screenshot](images/main%20table%20screenshot.png) | ![Custom Table Screenshot](images/custom%20table%20screenshot.png) |

## 🚀 Installation & Setup

Follow these commands to set up the project locally:

```bash
# Clone the repository
git clone https://github.com/stick231/Computer-equipment-accounting-systems.git

# Open project
cd Computer-equipment-accounting-systems

# Copy environment variables example to .env
mv .env.example .env 

# Build and run the containers in detached mode
docker-compose up --build -d 

# Run the migration script inside the container
docker exec -it <container_name_or_id> php /var/www/migration.php
# If use default settings run this command
docker exec -it webT php /var/www/migration.php
```

---

## 📝 Environment Variables

- You must create a `.env` file based on `.env.example` and update it with your actual configuration.

---

## 💡 Contributing

Feel free to fork the repo and submit pull requests!

---

## 📄 License

This project is licensed under the [MIT License](LICENSE).

---

**Happy accounting!**
