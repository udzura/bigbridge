FROM golang:1.10.3-stretch

RUN mkdir -p /go/src/attacker
WORKDIR /go/src/attacker
COPY . .

RUN go get -d -v ./...
RUN go install -v ./...

CMD ["attacker"]
