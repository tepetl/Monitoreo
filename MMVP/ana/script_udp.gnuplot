set datafile separator ","
set terminal png size 1152,864
set title "DP un dia"
set ylabel "nT"
set xlabel "Fecha-Hora"
set xdata time
set timefmt "%s"
set format x "%d/%h %H:%M"
set key left top
set grid

plot  "sal.txt" using 5:6 with dots title 'DP'
