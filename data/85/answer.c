#include <stdio.h>
int main(){
    int arr[10], k;
    for(int i=0; i<10; i++) scanf("%d", &arr[i]);
    scanf("%d", &k);
    printf("%d\n", arr[k-1]);
    return 0;
}
