### Code Block Tester

To test the example code blocks in the documentation pages, follow these steps:

1. In the root directory of this repo, build the base Docker image.
```
docker build -t example-regression-base-image -f examples-check/DockerfileRegressionBase .
```

2. Build the test Docker image.
```
docker build -t example-regression-test-image -f examples-check/DockerfileRegressionTest .
```

3. Run the script.
```
docker run -it --tmpfs /mnt/ramdisk:rw,size=1g -e DOCS_REGRESSION_TEST_USER_ID=<your_user_id> -e DOCS_REGRESSION_TEST_USER_TOKEN="<your_api_token>" example-regression-test-image
```

4. Open the container logs in Docker Desktop to see the errors detected in the code blocks.

It takes 10-15 minutes to gather all the code blocks in the documentation and then a few hours to test all them (if your organization has 10 backtest nodes).


### Goals

- No errors with compiling and backtesting code blocks.
- Code blocks complete backtesting within 1 minute. To find code blocks that take longer than 1 minute, run [this notebook](https://www.quantconnect.cloud/backtest/8ca67bce86612ecd88565cdd3514cc21/?theme=chrome).

### Development Notes:

- If you edit examples in the Documentation repository, to run the tests again, complete the preceding steps #1-#4.
- If only edit the `examples-check` source files, to run the tests again, complete the preceding steps #2-#4.
- To speed up your development process, you can place the commands from step #2 & #3 above into a `.sh` file. 
- To test local compile jobs for Python code snippets without running the testing script, run:
```
mypy <path_to_algorithm> --config-file examples-check/mypy.ini
```
