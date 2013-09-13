sudo rm -R Rpc/Feed/Reader/cache/*
sudo rm -R Rpc/Greek/Info/Namedays/cache/*
sudo rm -R Rpc/Html/HTMLReader/cache/*
sudo rm -R Rpc/Util/Cache/cache/*
sudo rm -R Docs/*

php phpDocumentor.phar --filename="*.php" -t Docs  --title="JsonRpc api Toolkit"

trash-put phpdoc*.log
