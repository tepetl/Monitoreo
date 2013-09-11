set datafile separator ","
set terminal png size 1152,864
set title "Alfas por día"
set ylabel ""
set xlabel "Fecha"
set xdata time
set timefmt "%s"
set format x "%d/%h"
set key left top
set grid
#set style line 3 lt 2 lc rgb "red" lw 0.25

plot [][]  "alfa.txt" using 4:1 with linespoints pt 13   title 'ALFA'
