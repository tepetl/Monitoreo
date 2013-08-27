#!/bin/bash
#
# Código de importación de TLA
#
# AAFR
#

cd $HOME/devel/importador_tla

for archivo in `find  $HOME/info/tla2/  -ctime -1 |grep "\.tla$"`
do
    echo $archivo
    md5sum $archivo >> $HOME/info/tla2/importado.txt
    php importa.php $archivo

done



cat $HOME/info/tla2/importado.txt |sort |uniq  > /tmp/importado_tla.txt
cp /tmp/importado_tla.txt $HOME/info/tla2/importado.txt

