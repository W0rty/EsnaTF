FROM python:latest

ARG user_script_name

RUN apt update && apt install python3-pip -y
RUN pip3 install requests
RUN groupadd bootcamp

RUN useradd -g bootcamp --home=/home/bootcamp bootcamp
COPY ${user_script_name} /home/bootcamp/script.py

CMD ["timeout","3","python3","/home/bootcamp/script.py"]
