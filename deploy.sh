if [ "$EUID" -ne 0 ]
  then echo "Please run as root"
  exit
fi

apt update

echo "[+] Installing apache2,mysql and php"
apt install apache2 >/dev/null 2>&1
rm /var/www/html/* >/dev/null 2>&1
cp -r src/*.* /var/www/html/ >/dev/null 2>&1
apt install mysql-server >/dev/null 2>&1

echo "[+] Starting mysql installation, please keep credentials with you"
mysql_secure_installation

echo "[+] Just hit enter when required please"
mysql -e 'CREATE DATABASE bdd'
mysql -p bdd < bdd.sql

apt install php libapache2-mod-php php-mysql >/dev/null 2>&1
a2enmod rewrite >/dev/null 2>&1

echo "[+] Restarting apache2 service"
systemctl restart apache2 >/dev/null 2>&1

echo "[+] Installing docker engine"
apt install apt-transport-https ca-certificates curl gnupg lsb-release >/dev/null 2>&1
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /usr/share/keyrings/docker-archive-keyring.gpg >/dev/null 2>&1
echo "deb [arch=amd64 signed-by=/usr/share/keyrings/docker-archive-keyring.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | tee /etc/apt/sources.list.d/docker.list >/dev/null
apt update >/dev/null 2>&1
apt install docker-ce docker-ce-cli containerd.io >/dev/null 2>&1

echo "[+] Modifying sudoers"
cat sudoers >> /etc/sudoers

echo "[+] Removing useless files and folders"
rm -rf src/
rm bdd.sql

echo "[+] Project has been installed successfully :)"
