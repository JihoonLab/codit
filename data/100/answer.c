#include <stdio.h>
int main() {
    int n;
    scanf("%d", &n);
    int hands[100];
    for (int i = 0; i < n; i++) scanf("%d", &hands[i]);
    // 1=가위, 2=바위, 3=보
    int wins[101] = {0}; // wins[i] = i번째 선수의 승수
    for (int i = 0; i < n; i++) {
        for (int j = i + 1; j < n; j++) {
            int a = hands[i], b = hands[j];
            if (a == b) continue;
            if ((a==1&&b==3)||(a==2&&b==1)||(a==3&&b==2)) wins[i]++;
            else wins[j]++;
        }
    }
    int maxWin = -1, winner = 0;
    for (int i = 0; i < n; i++) {
        if (wins[i] > maxWin) { maxWin = wins[i]; winner = i + 1; }
    }
    printf("%d\n", winner);
    return 0;
}
