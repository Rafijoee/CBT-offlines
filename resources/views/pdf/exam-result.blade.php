<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Hasil Ujian</title>

    <style>
        body {
            font-family: sans-serif;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 12px;
        }

        th {
            background: #f3f4f6;
        }
    </style>
</head>
<body>

    <h2>Data Hasil Ujian</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Mata Pelajaran</th>
                <th>Nilai</th>
                <th>Keterangan</th>
            </tr>
        </thead>

        <tbody>
            @foreach($results as $index => $result)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $result['nama_siswa'] }}</td>
                    <td>{{ $result['mapel'] }}</td>
                    <td>{{ $result['nilai'] }}</td>
                    <td>{{ $result['keterangan'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>