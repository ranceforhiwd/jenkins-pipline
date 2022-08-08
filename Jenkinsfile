pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                sh 'git-ftp push -u ${FTP_USERNAME} -p ${FTP_PASSWORD} 160.153.55.233'
            }
        }
        stage('Test') {
            steps {
                echo 'Testing a new feature.'
            }
        }
        stage('Deploy') {
            steps {
                echo 'Deployment completed by Rance....'
            }
        }
    }
}