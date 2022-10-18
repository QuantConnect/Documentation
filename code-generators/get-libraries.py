from os import path, popen

CMD = {
    'csharp': ["dotnet list ../Lean/QuantConnect.Lean.sln package",
               "dotnet list ../Lean/QuantConnect.Lean.sln package",
               "dotnet list ../Lean/QuantConnect.Lean.sln package",
               "dotnet list ../Lean/QuantConnect.Lean.sln package"],
    'python': ['docker run --entrypoint bash quantconnect/lean:latest -c "pip list"',
               'docker run --entrypoint bash quantconnect/lean:latest -c "pip list"',
               'docker run --entrypoint bash quantconnect/lean:latest -c ". /Foundation-Pomegranate/bin/activate && pip list"',
               'docker run --entrypoint bash quantconnect/lean:latest -c ". /Foundation-Tensorforce/bin/activate && pip list"']
}
filenames = ['Resources/libraries/supported-libraries.php',
             'Resources/libraries/supported-libraries-cloud.php',
             'Resources/libraries/supported-libraries-foundation-pomegranate.php',
             'Resources/libraries/supported-libraries-foundation-tensorforce.php']

cloud_added = {
    "Accord": "3.6.0",
    "Accord.Audio": "3.6.0",
    "Accord.Fuzzy": "3.6.0",
    "Accord.Genetic": "3.6.0",
    "Accord.MachineLearning": "3.6.0",
    "Accord.MachineLearning.GPL": "3.6.0",
    "Accord.Math": "3.6.0",
    "Accord.Statistics": "3.6.0",
    "Deedle": "2.1.0",
    "DynamicInterop": "0.9.1",
    "Google.OrTools": "9.4.1874",
    "MathNet.Filtering": "0.7.0",
    "MathNet.Filtering.Kalman": "0.7.0",
    "MathNet.Numerics": "4.15.0",
    "MathNet.Spatial": "0.6.0",
    "Microsoft.Data.Analysis": "0.19.1",
    "Microsoft.ML": "1.7.1",
    "Microsoft.ML.CpuMath": "1.7.1",
    "Microsoft.ML.DataView": "1.7.1",
    "Microsoft.ML.Ensemble": "0.19.1",
    "Microsoft.ML.FastTree": "1.7.1",
    "Microsoft.ML.LightGbm": "1.7.1",
    "Microsoft.ML.Mkl.Components": "1.7.1",
    "Microsoft.ML.OnnxRuntime": "1.12.1",
    "Microsoft.ML.TensorFlow": "1.7.1",
    "Microsoft.ML.TimeSeries": "1.7.1",
    "Newtonsoft.Json": "12.0.3",
    "NodaTime": "3.0.5",
    "Plotly.NET": "3.0.1",
    "Plotly.NET.Interactive": "3.0.2",
    "QLNet": "1.12.0",
    "R.NET": "1.9.0",
    "RestSharp": "106.12.0",
    "Catalyst": "1.0.31087",
    "Catalyst.Models.English": "1.0.30952",
    "CNTK.CPUOnly": "2.8.0-rc0.dev20200201",
    "LibTopoART": "0.96.0",
    "NumSharp": "0.30.0",
    "SharpLearning.DecisionTrees": "0.31.8",
    "SharpLearning.AdaBoost": "0.31.8",
    "SharpLearning.RandomForest": "0.31.8",
    "SharpLearning.GradientBoost": "0.31.8",
    "SharpLearning.Neural": "0.31.8",
    "SharpLearning.Ensemble": "0.31.8",
    "SharpLearning.Common.Interfaces": "0.31.8",
    "SharpLearning.CrossValidation": "0.31.8",
    "SharpLearning.Metrics": "0.31.8",
    "SharpLearning.Optimization": "0.31.8",
    "SharpLearning.Containers": "0.31.8",
    "SharpLearning.InputOutput": "0.31.8",
    "SharpLearning.FeatureTransformations": "0.31.8",
    "SharpLearning.XGBoost": "0.31.8",
    "SharpNeatLib": "2.4.4",
    "TensorFlow.Keras": "0.7.0",
    "TensorFlow.NET": "0.70.1"
}

if not path.isfile("../Lean/QuantConnect.Lean.sln"):
    popen("git clone https://github.com/QuantConnect/Lean.git ../Lean").close()
    popen("dotnet restore ../Lean/QuantConnect.Lean.sln").close()

for j, filename in enumerate(filenames):
    with open(filename, mode='w', encoding='utf-8') as fp:
        fp.write('<div class="section-example-container">')

    for language, cmds in CMD.items():
        cmd = cmds[j]
        content = popen(cmd)
        maxlen, libraries = 0, {'# Name': 'Version'}

        for i, line in enumerate(content.readlines()):
            if i < 2: continue
            if "WARNING" in line: break
            
            line = [x for x in line.split(' ') if x != '']
            
            if language == "csharp":
                if ">" not in line: continue
                
                if line[-1] != "\n":
                    key, value = [line[1]] + [line[-1].replace("\n", "")]
                else:
                    key, value = [line[1]] + [line[-2].replace("\n", "")]
            else:
                key, value = line[:2]
            maxlen = max(maxlen, len(key))
            libraries[key] = value

        if "cloud" in filename and language == "csharp":
            libraries = {**libraries, **cloud_added}

        with open(filename, mode='a', encoding='utf-8') as fp:
            fp.write(f'<pre class="{language}">')
            for key, value in sorted(libraries.items(), key=lambda x: x[0].lower()):
                count = maxlen - len(key)
                value = value.replace("\n", "")
                fp.write(f'{key + " " * count} {value}\n')
            fp.write('</pre>')

        print(f'{filename} file written with {len(libraries)-1} entries')
        
    with open(filename, mode='a', encoding='utf-8') as fp:
        fp.write('</div>')