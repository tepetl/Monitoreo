# http://blog.mague.com/?p=201


set datafile separator ","
set terminal png size 900,400
set title "Estaciones"
set ylabel "nT"
set xlabel "Fecha-Hora"
set xdata time
set timefmt "%s"
set format x "%d/%h"
set key left top
set grid
plot "salida.teo" using 1:2 with lines lw .7 lt 3 title 'TEO',\
     "../importador_tla/salida.tla" using 1:2 with lines lw .7 lt 1 title 'TLA'