#!/bin/bash

# Define the .env file path
ENV_FILE="/var/www/affiniaProm/.env"

# Create the .env file with the specified values
cat > "$ENV_FILE" << EOF
APP_ENV=dev
APP_SECRET=$(openssl rand -hex 16)
DATABASE_URL="mysql://heavenuser:7Wb&Y-NwH&XSv-H@127.0.0.1:3306/affiniaprm?serverVersion=10.11.2-MariaDB&charset=utf8mb4"
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0&table_name=messenger_messages
MAILER_DSN=smtp://3ddd17d94bcfbd:208a42da9778ff@sandbox.smtp.mailtrap.io:2525
APP_MAINTENANCE=0
RECAPTCHA3_KEY=6LfuI_AoAAAAADDPTFRgx1M804baN7BDrX3Ak6-t
RECAPTCHA3_SECRET=6LfuI_AoAAAAAL3aBv_jLv6wh7Noenf-ICZJX8-h
RECAPTCHA3_ENABLED=1
WEBSITE_NAME="GrandBridge"
TIMEZONE="Africa/Algiers"
WEBSITE_HOST="127.0.0.1"
WEBSITE_HTTPS=1
WEBSITE_URL="https://127.0.0.1:8000"
EOF

# List of directories to create
declare -a directories=(
    "/public/uploads/employee"
    "/public/uploads/customer"
    "/public/uploads/resumes"
    "/public/uploads/project"
    "/public/uploads/documents/attestations"
    "/public/uploads/albums"
    "/public/uploads/public"
    "/public/uploads/qr"
    "/public/uploads/the_company"
    "/public/uploads/documents"
    "/public/uploads/accounting_documents"
    "/public/uploads/user"
    "/public/uploads/images/user"
    "/public/uploads/blog"
)

# Base path for the directories
BASE_PATH="/var/www/affiniaProm/"

# Create directories and set www-data as owner
for dir in "${directories[@]}"; do
    mkdir -p "$BASE_PATH$dir"
    chown www-data:www-data "$BASE_PATH$dir" -R
done

echo "Setup folders done."

# Navigate to the parent directory
# shellcheck disable=SC2164
cd /var/www/affiniaProm

php bin/console app:addWilayas
php bin/console app:createStatesAndTypes
php bin/console app:launchProccess
php bin/console app:createTypesReal
php bin/console app:createTodoListsTemplates
php bin/console app:createBankAccounts
php bin/console app:createComnay

