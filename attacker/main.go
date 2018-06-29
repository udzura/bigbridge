package main

import (
	"fmt"
	"gopkg.in/headzoo/surf.v1"
	"os"
)

var stage = ""
var target = ""

func debug(format string, arg ...interface{}) {
	if os.Getenv("DEBUG") == "1" {
		fmt.Printf(format, arg...)
	}
}

func main() {
	if len(os.Args) < 3 {
		panic("Usage: attacker [STAGE_NR] [TARGET_HOST]")
	}
	stage = os.Args[1]
	target = os.Args[2]
	var err error

	switch stage {
	case "1":
		err = runStage1()
	default:
		panic("Invalid stage")
	}

	if err == nil {
		fmt.Printf("防衛に成功しました!!!\n")
	} else {
		fmt.Printf("防衛に失敗しています:\n%s", err.Error())
		os.Exit(1)
	}

}

func runStage1() error {
	bow := surf.NewBrowser()
	err := bow.Open(target + "/app/app.php")
	if err != nil {
		return err
	}

	loginForm, _ := bow.Form("[id='main']")
	loginForm.Input("name", "test-user")
	loginForm.Input("message", "Hello\n<script>alert('3np1+!');</script>")
	if err := loginForm.Submit(); err != nil {
		return err
	}

	found := bow.Find("script").Size()
	debug("Script element size: %v\n", found)
	if found == 0 {
		return nil
	} else {
		return fmt.Errorf("攻撃が成功しました!!!\n防衛のためにコンテナを修正してください。\n")
	}
}
