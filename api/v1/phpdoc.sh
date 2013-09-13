# Clear cache directories
sudo rm -R Rpc/Feed/Reader/cache/*
sudo rm -R Rpc/Html/HTMLReader/cache/*
sudo rm -R Rpc/Util/Cache/cache/*
sudo rm -R Docs/*
sudo rm -R Rpc/Greek/Info/Namedays/cache/*

# Run php-cs-fixer
# If you don't have it get it from
# https://github.com/fabpot/PHP-CS-Fixer
php-cs-fixer fix ./Rpc

# Run php-cs-fixer
php phpDocumentor.phar --filename="*.php" -t Docs  --title="JsonRpc api Toolkit"

# Move log files to trash in case you want to examine them
# sudo apt-get install trash-cli
trash-put phpdoc*.log
