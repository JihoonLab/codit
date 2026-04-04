#include <stdio.h>
int main(){
    int a, b;
    scanf("%d %d", &a, &b);
    int sum = 0, week = 0;
    while(sum < b){
        sum += a;
        week++;
    }
    printf("%d\n", week);
    return 0;
}
