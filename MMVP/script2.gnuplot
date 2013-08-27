set datafile separator ","
set terminal png size 1152,864
set title "DP"
set ylabel "nT"
set xlabel "Fecha-Hora"
set xdata time
set timefmt "%s"
set format x "%d/%h %H:%M"
set key right top
set grid
set ytics 1

#plot ["1370012340":"1376489740"][-10:10]  "sal.txt" using 5:6 with points pt 13 title 'DP', "sal.txt" u 5:7 with lines lw 0.75 linecolor rgb "blue" title 'alfa', 0.5480350233 with lines lw 1 linecolor rgb "violet" title 'AvDP '#


#plot [][]  "sal.txt" using 5:6 with points pt 13 title 'DP', -0.6791957942 with lines lw 1 linecolor rgb "blue" title 'AvDP '
#

plot [][-10:10]  "sal.txt" using 5:6 with points pt 13 title 'DP', 0.4211152395 with lines lw 1 linecolor rgb "blue" title 'AvDP '

#plot [][-10:10]  "sal.txt" using 5:6 with points pt 13 title 'DP'#
