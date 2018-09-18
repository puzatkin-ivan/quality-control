set PROGRAM="%~1"

%PROGRAM% > output.txt
if not errorlevel 1 goto error
fc output.txt  test/error_input.txt
if errorlevel 1 goto error

%PROGRAM% 1  > output.txt
if not errorlevel 1 goto error
fc output.txt  test/error_input.txt
if errorlevel 1 goto error

%PROGRAM% 1 2 > output.txt
if not errorlevel 1 goto error
fc output.txt  test/error_input.txt
if errorlevel 1 goto error

%PROGRAM% a b c > output.txt
if not errorlevel 1 goto error

%PROGRAM% 1 2 3 4 > output.txt
if not errorlevel 1 goto error
fc output.txt  test/error_input.txt
if errorlevel 1 goto error

%PROGRAM% a 3 c > output.txt
if not errorlevel 1 goto error

%PROGRAM% 1 2 3 > output.txt
if not errorlevel 1 goto error
fc output.txt  test/no_triangle.txt
if errorlevel 1 goto error

%PROGRAM% 31 31 31  > output.txt
if errorlevel 1 goto error
fc output.txt  test/equilateral_triangle.txt
if errorlevel 1 goto error

%PROGRAM% 3.1 3.1 3.1  > output.txt
if errorlevel 1 goto error
fc output.txt  test/equilateral_triangle.txt
if errorlevel 1 goto error

%PROGRAM% 3,1 3,1 3,1  > output.txt
if errorlevel 1 goto error
fc output.txt  test/equilateral_triangle.txt
if errorlevel 1 goto error

%PROGRAM% 10 15 15  > output.txt
if errorlevel 1 goto error
fc output.txt  test/isosceles_triangle.txt
if errorlevel 1 goto error

%PROGRAM% 5 3 4  > output.txt
if errorlevel 1 goto error
fc output.txt  test/simple_triangle.txt
if errorlevel 1 goto error

echo Program testing succeeded
exit 0

:error
echo Test failed
exit 1