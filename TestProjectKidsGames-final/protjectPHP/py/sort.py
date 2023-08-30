def sort_string(string):
    sorted_string = ''.join(sorted(string, key=lambda x: x.lower()))
    return sorted_string

# Example usage
input_string = "f l z d a C"
sorted_string = sort_string(input_string)
print(sorted_string)
