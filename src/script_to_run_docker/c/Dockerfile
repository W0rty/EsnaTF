FROM ubuntu:latest

ARG user_script_name
ARG exercice

RUN apt update && apt install python3 gcc python3-pip -y
RUN pip3 install pwntools
RUN groupadd bootcamp

RUN useradd -g bootcamp --home=/home/bootcamp bootcamp
COPY ${user_script_name} /home/bootcamp/script.c
COPY ./${exercice}/* /home/bootcamp/
WORKDIR /home/bootcamp
CMD ["python3","code_tester.py"]