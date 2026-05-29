# Konversi Infix ke Postfix
def infix_to_postfix(expression):
    precedence = {'+': 1, '-': 1, '*': 2, '/': 2}
    stack = []
    output = []

    for ch in expression.split():
        if ch.isnumeric():  # operand
            output.append(ch)
        elif ch in precedence:  # operator
            while stack and precedence.get(stack[-1], 0) >= precedence[ch]:
                output.append(stack.pop())
            stack.append(ch)
        elif ch == '(':
            stack.append(ch)
        elif ch == ')':
            while stack and stack[-1] != '(':
                output.append(stack.pop())
            stack.pop()  # hapus '(' dari stack

    while stack:
        output.append(stack.pop())

    return ' '.join(output)

# Contoh penggunaan
expr = "10 + 7 * 8 - 2 + 10 * 2"
postfix = infix_to_postfix(expr)
print("Postfix:", postfix)