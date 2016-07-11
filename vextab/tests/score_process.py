import re

out = []
with open('./score_playAll.txt', 'r') as f:
    l =  f.read().split()
    dur = ""
    note_pattern = re.compile("[A-G]")
    rest_pattern = "##"
    for s in l:
        if s[0] == ':': 
            dur = s
        elif note_pattern.match(s):
            if "-" in s:
                octaveIndex = s.index("/") + 1
                octave = "/" + s[octaveIndex]
                joined_shared = []
                notes = s.split("-")
                for i in range(len(notes) - 1):
                    joined = "".join([dur,notes[i],octave])
                    joined_shared.append(joined)
                last = "".join([dur,notes[-1]])
                joined_shared.append(last)
                out = out + joined_shared
            else:
                joined = "".join([dur,s])
                out.append(joined)
        elif s == rest_pattern or s[0] == "(":
            joined = "".join([dur,s])
            out.append(joined)
        else:
            out.append(s)

with open('./score_alltime.txt', 'wb') as wf:
    wf.write(" ".join(out))
