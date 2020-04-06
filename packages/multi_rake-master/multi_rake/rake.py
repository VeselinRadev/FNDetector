#!/usr/bin/env python
import os
import sys
import numpy as np
from algorithm import Rake

sys.path.insert(0, os.path.dirname(__file__))

def rake(environ, start_response):
    # text = (
    # )
    rake = Rake(
        min_chars=0,
        max_words=2,
        min_freq=1,
        language_code="bg")
    
    keywords = rake.apply(text)
    output = ""
    i = 0
    for word in keywords:
        output += word[0]
        if i == len(keywords) or i == 10:
            break
        output += ", "
        i += 1
    start_response('200 OK', [('Content-Type', 'text/plain')])
    return [output.encode()]
