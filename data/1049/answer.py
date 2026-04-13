a, b = map(int, input().split())

if a == 1:
    cal_a = 400
elif a == 2:
    cal_a = 340
elif a == 3:
    cal_a = 170
else:
    cal_a = 100

if b == 1:
    cal_b = 400
elif b == 2:
    cal_b = 340
elif b == 3:
    cal_b = 170
else:
    cal_b = 100

if cal_a + cal_b > 600:
    print("angry")
else:
    print("no angry")
