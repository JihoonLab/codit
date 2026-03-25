import sys
for line in sys.stdin:
    for c in line.split():
        print(c)
        if c == 'q':
            exit()
