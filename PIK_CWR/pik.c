#define _GNU_SOURCE
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/stat.h>

int match(char *buff, char* word){
    int j = 0;
    int i,counter=0;

    for(i = 0;i < strlen(buff);i++){
        if(buff[i] == word[j]){
            if(j == strlen(word)-1){
                counter++;
                j = 0;
                continue;
            }
        }else{
            j = 0;
            continue;
        }
        j++;

    }
    //printf("%d the counter shows",counter);
    return counter;
}

void operator_check(char *buff){
    int result;
    result = match(buff,"while");
    printf("\nresult:%d\n",result);

}

char* file_read(){
    FILE *fp;
    char filename[] = "1.txt";
    struct stat file_status;
    char *line_buff = NULL;
    char *file_buff = NULL;

    //writes info about the file to file_status
    stat(filename, &file_status);
    //printf("%d\n", file_status.st_size);

    // file_status.st_size - the size of the file
    //dynamically allocate memory
    line_buff = (char*)malloc(file_status.st_size);
    file_buff = (char*)malloc(file_status.st_size);
    strcpy(file_buff, "");

    //opens the file
    fp = fopen(filename, "r");

    if (fp == NULL){
        printf("Error\n");
        exit(EXIT_FAILURE);
    }

    while (fgets(line_buff,file_status.st_size, fp)!=NULL){
        printf("%s", line_buff);
        strcat(file_buff, line_buff);
     }

    free(line_buff);
    fclose(fp);
    return file_buff;
    free(file_buff);
}

int main(){
    char *result;
    result = file_read();
    operator_check(result);
    return 0;
}
