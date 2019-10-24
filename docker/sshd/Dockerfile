FROM alpine:latest
EXPOSE 22
VOLUME /educadex

RUN apk add --no-cache openssh openssl rsync \
  && sed -i s/#PermitRootLogin.*/PermitRootLogin\ yes/ /etc/ssh/sshd_config \
  && ssh-keygen -A \
  && mkdir /root/.ssh \
  && echo "root:"$(openssl rand -base64 32) | chpasswd \
  && echo 'ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAACAQDaSXqFmelnkeAHFfD/z+IbiG7iilqU11KCmqQzJzVI2EK1s+PTuIimyIZ4K2XQK/PccbqHL2N4xMdFaSLPi39KBU0HtSq+cw3sD9s2rK69t85Lwc9QWTEw0a050BOpn4vSgEX0c6pqqQr7tEOC/dK3IL4f9q+IQKVz/PeqGh6wVLjQo7UYW3H6WraUIEdZfLzolVqoFgqVmdDVOQNp/RfBIpG+4p/2gLgqTIT8W7Wy0R8d4kQPeTu08cOFIAjigNilaYzLo9lSq8N0y0abIosUca4Hg1HBx41wKhzL+mw5SW9NGd6tY94sxsY/xPZMf/VqkmMCKrwlQCbPP0eRkb6Pmi3+4RSz45oLKAbb7ZDmSarBEhPud+20L/z6t4fLsCqt/+VHTU3c8ej32hzg0gJYNLM5yVgM8YqlIhGs5X/P2QgFmfac4LajUpTbMuu4g1b8HP/Wid4siBfnXKdfdOuLDc8CDUs1nRbCKPlTV7pGGCkf4byRblrje8sqK7LMbVLtTk4lfs7vjBnv07Af6iAfIZyYwflh6WOLL1eMEh8AxqFeGE+Rk0YCqkewkjoJtbxRwowcAoi+ggoxdGRxFPyFIF7850drFjmqSrzaik30FcZFsomXQuh3z/SclP6jLnaFbnt/nWffBNbGfdFQ8Tld1e8cSDWEHG6ubDO07aql0w== emiliopedrollo@gmail.com' >> /root/.ssh/authorized_keys

CMD ["/usr/sbin/sshd","-D","-e"]
