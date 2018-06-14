package main

import (
	"fmt"
	"gopkg.in/headzoo/surf.v1"
	"os"
)

func main() {
	bow := surf.NewBrowser()
	err := bow.Open("http://localhost:18080/app.php")
	if err != nil {
		panic(err)
	}

	err = bow.Open("http://localhost:18080/app.php?name=udzura<script>alert(1);</script>")
	if err != nil {
		panic(err)
	}
	found := bow.Find("script").Size()
	fmt.Printf("Script element size: %v\n", found)
	if found == 0 {
		fmt.Printf("Defence success!!! Omedetou!!!!!!!!!!!!\n")
	} else {
		fmt.Printf("Attack success!!!\n")
		os.Exit(1)
	}

}
