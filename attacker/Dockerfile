FROM golang:1.10.3-stretch

RUN mkdir -p /go/src/attacker
WORKDIR /go/src/attacker
COPY . .

RUN go get -d -v ./...
RUN go install -v ./...

ADD main.sh /usr/local/bin/main.sh
RUN chmod a+x /usr/local/bin/main.sh

ENTRYPOINT ["/usr/local/bin/main.sh"]
