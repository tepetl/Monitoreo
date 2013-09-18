##
# 
#
##
cd $HOME/devel/importador_teo

for archivo in `find /home/tepetl/info/2012/INTERMAGNET/ -ctime -1  |grep "TEO$" |grep -v "A\." |grep -v "/[0-9]" |sort `
do 

echo $archivo
 md5sum $archivo >> $HOME/info/2012INTERMAGNET/importado.txt
php importa.php $archivo 2012

done 

cat $HOME/info/2012/INTERMAGNET/importado.txt |sort |uniq  > /tmp/importado_teo.txt
cp /tmp/importado_teo.txt $HOME/info/2012/INTERMAGNET/importado.txt
