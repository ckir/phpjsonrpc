sudo rm -R Local/Feed/Reader/cache/*
sudo rm -R Local/Greek/Info/Namedays/cache/*
sudo rm -R Local/Html/HTMLReader/cache/*
sudo rm -R Local/Util/Cache/cache/*
sudo rm -R Docs/*

php phpDocumentor.phar --filename="*.php" -t Docs  --title="JsonRpc api Toolkit"

trash-put phpdoc*.log
