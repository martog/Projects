#define _GNU_SOURCE
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/stat.h>

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
   //exit(EXIT_SUCCESS);
    return file_buff;
    free(file_buff);
}

void sym_check(char *buff){
    printf("\nsym check called...\n");
    int buff_size = strlen(buff);
    int counter = 0;
    int new_lines = 0;

    while(counter < buff_size){
        //printf("%c", buff[counter]);
        if(buff[counter] == '\n'){
            //printf("//there is new line//");
            new_lines++;
        }
        counter++;
    }
    //printf("%s",buff);
    //printf("\nsizeof_buff = %d\n", buff_size);
    printf("\nnew_lines:%d\n",new_lines);

}

int main(){
    char *result;
    result = file_read();
    sym_check(result);
    return 0;
}
