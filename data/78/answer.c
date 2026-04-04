#include <stdio.h>
int main(){
    int n, m;
    scanf("%d %d", &n, &m);
    int sum = 0, day = 0;
    for(int i = 0; i < n; i++){
        int x;
        scanf("%d", &x);
        sum += x;
        if(sum > m){
            day = i + 1;
            break;
        }
    }
    if(day > 0)
        printf("%d\n", day);
    else
        printf("SAFE\n");
    return 0;
}
