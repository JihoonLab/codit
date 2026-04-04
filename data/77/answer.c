#include <stdio.h>
int main(){
    int n, g;
    scanf("%d %d", &n, &g);
    int count = 0;
    for(int i = 0; i < n; i++){
        int x;
        scanf("%d", &x);
        if(x >= g)
            count++;
    }
    printf("%d\n", count);
    return 0;
}
