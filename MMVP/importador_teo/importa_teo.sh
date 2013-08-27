
cd $HOME/devel/importador_teo

for archivo in `find /home/tepetl/info/INTERMAGNET/ -ctime -1  |grep "TEO$" |grep -v "A\." |grep -v "/[0-9]" |sort `
do 

echo $archivo
 md5sum $archivo >> $HOME/info/INTERMAGNET/importado.txt
php importa.php $archivo

done 

cat $HOME/info/INTERMAGNET/importado.txt |sort |uniq  > /tmp/importado_teo.txt
cp /tmp/importado_teo.txt $HOME/info/INTERMAGNET/importado.txt
