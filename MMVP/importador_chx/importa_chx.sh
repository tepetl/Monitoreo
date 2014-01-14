##
# 
#
##
cd $HOME/devel/importador_chx

for archivo in `find /home/tepetl/info/chi/ -ctime -1  |grep "txt$" |grep "CHI" |sort`
do 

echo $archivo
 md5sum $archivo >> $HOME/info/chi/importado.txt
php importa.php $archivo

done 

cat $HOME/info/chi/importado.txt |sort |uniq  > /tmp/importado_chi.txt
cp /tmp/importado_chi.txt $HOME/info/chi/importado.txt
