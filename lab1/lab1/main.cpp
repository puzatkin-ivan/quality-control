#include "stdafx.h"
#include <exception>
#include <iostream>
#include <string>
#include <functional>

class Triangle
{
public:
	Triangle(double a, double b, double c)
		:a(a), b(b), c(c), type(SIMPLE)
	{
		if (!isValid())
		{
			throw std::invalid_argument("The shape isn't triangle");
		}

		type = SelectType();
	}

	bool isValid()
	{
		return ((a + b > c) && (a + c > b) && (b + c > a));
	}

	std::string getType()
	{
		return type;
	}

private:
	std::string SelectType()
	{
		if ((a == b) && (b == c))
		{
			return EQUILATERAL;
		}

		if ((a == b) || (b == c) || (c == a))
		{
			return ISOSCELES;
		}
		return SIMPLE;
	}

	double a;
	double b;
	double c;
	std::string type;

	static std::string ISOSCELES;
	static std::string EQUILATERAL;
	static std::string SIMPLE;
};

std::string Triangle::ISOSCELES = std::string("Isosceles triangle");
std::string Triangle::EQUILATERAL = std::string("Equilateral triangle");
std::string Triangle::SIMPLE = std::string("Simple triangle");

std::string Replace(const std::string & inputStr, const std::string & searchStr, const std::string & replacementStr);
std::string doExecute(int argc, char* argv[]);
Triangle getTriangle(char* vertices[]);
double getVertex(const std::string& vertex);

int main(int argc, char* argv[])
{
	try
	{
		std::cout << doExecute(argc, argv) << std::endl;
	}
	catch (std::exception& exception)
	{
		std::cout << exception.what() << std::endl;
		return 1;
	}

    return 0;
}

std::string doExecute(int argc, char* argv[])
{
	if (argc != 4)
	{
		throw std::invalid_argument(
			"Error: invalid arguments count\nUsage: lab1.exe <vertex1> <vertex2> <vertex3>"
		);
	}

	auto triangle = getTriangle(argv);

	
	return triangle.getType();
}

Triangle getTriangle(char* vertices[])
{
	auto a = getVertex(std::string(vertices[1]));
	auto b = getVertex(std::string(vertices[2]));
	auto c = getVertex(std::string(vertices[3]));

	return Triangle(a, b, c);
}

double getVertex(const std::string& str)
{
	try
	{
		std::string digit(Replace(str, ",", "."));
		

		auto result = std::stod(digit);
		return result;
	}
	catch (std::invalid_argument& exception)
	{
		(void)&exception;

		throw std::invalid_argument("The '" + str + "' isn't double.");
	}
}

std::string Replace(const std::string & inputStr, const std::string & searchStr, const std::string & replacementStr)
{
	if (searchStr.empty())
	{
		return inputStr;
	}
	std::string resultStr;
	size_t initSearchPos = 0;

	while (initSearchPos != std::string::npos)
	{
		auto foundPos = inputStr.find(searchStr, initSearchPos);
		resultStr.append(inputStr, initSearchPos, foundPos - initSearchPos);

		if (foundPos != std::string::npos)
		{
			resultStr.append(replacementStr);
			foundPos += searchStr.size();
		}
		initSearchPos = foundPos;
	}

	return resultStr;
}
