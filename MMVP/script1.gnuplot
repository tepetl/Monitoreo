set datafile separator ","
set terminal png size 1152,864
set title "DP"
set ylabel "nT"
set xlabel "Fecha-Hora"
set xdata time
set timefmt "%s"
set format x "%d/%h %H:%M"
set key left top
set grid
#set style line 3 lt 2 lc rgb "red" lw 0.25

plot [][]  "sal.txt" using 5:6 with linespoints pt 13   title 'DP'
