#include <stdio.h>
int main()
{
    char s[1000];
    fgets(s, sizeof(s), stdin);
    int len = 0;
    while (s[len]) len++;
    if (len > 0 && s[len-1] == '\n') s[len-1] = '\0';
    printf("%s", s);
    return 0;
}
