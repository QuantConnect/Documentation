"""C# code compilation and validation."""
import os
import subprocess

from compilers.compiler import Compiler


class CSharpCompiler(Compiler):
    """Handles C# code compilation and validation."""

    # Class attributes for base class pattern matching
    DATE_RANGE_PATTERNS = ['SetStartDate(', 'SetEndDate(']
    ALGORITHM_CLASS_PATTERN = ': QCAlgorithm'

    # C# using statements
    IMPORTS = """using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Globalization;
using System.Drawing;
using QuantConnect;
using QuantConnect.Algorithm.Framework;
using QuantConnect.Algorithm.Framework.Selection;
using QuantConnect.Algorithm.Framework.Alphas;
using QuantConnect.Algorithm.Framework.Portfolio;
using QuantConnect.Algorithm.Framework.Portfolio.SignalExports;
using QuantConnect.Algorithm.Framework.Execution;
using QuantConnect.Algorithm.Framework.Risk;
using QuantConnect.Algorithm.Selection;
using QuantConnect.Api;
using QuantConnect.Parameters;
using QuantConnect.Benchmarks;
using QuantConnect.Brokerages;
using QuantConnect.Commands;
using QuantConnect.Configuration;
using QuantConnect.Util;
using QuantConnect.Interfaces;
using QuantConnect.Algorithm;
using QuantConnect.Indicators;
using QuantConnect.Data;
using QuantConnect.Data.Auxiliary;
using QuantConnect.Data.Consolidators;
using QuantConnect.Data.Custom;
using QuantConnect.Data.Custom.IconicTypes;
using QuantConnect.DataSource;
using QuantConnect.Data.Fundamental;
using QuantConnect.Data.Market;
using QuantConnect.Data.Shortable;
using QuantConnect.Data.UniverseSelection;
using QuantConnect.Notifications;
using QuantConnect.Orders;
using QuantConnect.Orders.Fees;
using QuantConnect.Orders.Fills;
using QuantConnect.Orders.OptionExercise;
using QuantConnect.Orders.Slippage;
using QuantConnect.Orders.TimeInForces;
using QuantConnect.Python;
using QuantConnect.Scheduling;
using QuantConnect.Securities;
using QuantConnect.Securities.Equity;
using QuantConnect.Securities.Future;
using QuantConnect.Securities.Option;
using QuantConnect.Securities.Positions;
using QuantConnect.Securities.Forex;
using QuantConnect.Securities.Crypto;
using QuantConnect.Securities.CryptoFuture;
using QuantConnect.Securities.IndexOption;
using QuantConnect.Securities.Interfaces;
using QuantConnect.Securities.Volatility;
using QuantConnect.Storage;
using QuantConnect.Statistics;
using QuantConnect.Indicators.CandlestickPatterns;
using QCAlgorithmFramework = QuantConnect.Algorithm.QCAlgorithm;
using QCAlgorithmFrameworkBridge = QuantConnect.Algorithm.QCAlgorithm;
using Calendar = QuantConnect.Data.Consolidators.Calendar;
"""

    # C# project file template
    PROJECT_FILE = """
<Project Sdk="Microsoft.NET.Sdk">
    <PropertyGroup>
        <Configuration Condition=" '$(Configuration)' == '' ">Debug</Configuration>
        <Platform Condition=" '$(Platform)' == '' ">AnyCPU</Platform>
        <TargetFramework>net9.0</TargetFramework>
        <OutputPath>bin/$(Configuration)</OutputPath>
        <AppendTargetFrameworkToOutputPath>false</AppendTargetFrameworkToOutputPath>
        <DefaultItemExcludes>$(DefaultItemExcludes);backtests/*/code/**;live/*/code/**;optimizations/*/code/**</DefaultItemExcludes>
        <NoWarn>CS0618</NoWarn>
    </PropertyGroup>
    <ItemGroup>
        <PackageReference Include="QuantConnect.Lean" Version="2.5.*"/>
        <PackageReference Include="QuantConnect.DataSource.Libraries" Version="2.5.*"/>
    </ItemGroup>
</Project>
"""

    def __init__(self):
        """Initialize the compiler and write the csproj file once."""
        with open(f"{self._ramdisk_path}/project.csproj", "w") as f:
            f.write(self.PROJECT_FILE)

    def compile_fragment(self, code):
        """
        Compile a C# code fragment.

        Returns:
            Error message if compilation fails, None if successful.
        """
        # Write the algorithm file.
        with open(f"{self._ramdisk_path}/test.cs", "w") as f:
            f.write(self.prepare_for_backtest(code))
        # Compile it.
        proc = subprocess.run(
            [
                'dotnet',
                'build',
                '.',
                '--nologo',
                '-v:q',
                '-clp:ErrorsOnly;NoSummary;DisableConsoleColor'
            ],
            cwd=self._ramdisk_path,
            capture_output=True,
            text=True,
            env={
                **os.environ,
                "DOTNET_SYSTEM_CONSOLE_ALLOW_ANSI_COLOR_REDIRECTION": "0",
                "DOTNET_CLI_TELEMETRY_OPTOUT": "1",
                "DOTNET_NOLOGO": "1",
                "TERM": "dumb",
            }
        )
        # Return the errors if there are any.
        if proc.returncode:
            return proc.stdout
        return None
