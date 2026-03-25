#include <stdio.h>
int main(){
    int n;
    scanf("%d",&n);
    switch(n){
        case 1: case 3: case 5: case 7:
            printf("oh my god"); break;
        default:
            printf("enjoy"); break;
    }
    return 0;
}
