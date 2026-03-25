#include <stdio.h>
int main(){
    char g;
    scanf(" %c",&g);
    switch(g){
        case 'A': printf("best!!!"); break;
        case 'B': printf("good!!"); break;
        case 'C': printf("run!"); break;
        case 'D': printf("slowly~"); break;
        default: printf("what?"); break;
    }
    return 0;
}
